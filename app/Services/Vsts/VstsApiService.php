<?php
/**
 * Created by PhpStorm.
 * Member: andreas
 * Date: 23/01/2017
 * Time: 18:02
 */

namespace App\Services\Vsts;

use App\Models\Commit;
use App\Models\User;
use App\Models\VstsAccount;
use App\Models\VstsProject;
use App\Repositories\Commit\CommitRepoInterface;
use App\Repositories\VstsAccount\VstsAccountRepo;
use App\Repositories\VstsProject\VstsProjectRepoInterface;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Providers\User\UserInterface;


class VstsApiService
{
    private $client;
    private $userRepo;
    private $vstsAccountRepo;
    private $vstsProjectRepo;
    private $commitRepo;


    public function __construct( UserInterface $userRepo, VstsAccountRepo $vstsAccountRepo, VstsProjectRepoInterface $vstsProjectRepo, CommitRepoInterface $commitRepo )
    {
        $this -> client = new Client();
        $this -> userRepo = $userRepo;
        $this -> vstsAccountRepo = $vstsAccountRepo;
        $this -> vstsProjectRepo = $vstsProjectRepo;
        $this -> commitRepo = $commitRepo;
    }


    public function getAuthorizationURL( User $user )
    {
        $url = 'https://app.vssps.visualstudio.com/oauth2/authorize';

        $parameters = http_build_query([

            'client_id' => env( 'VSTS_APP_ID' ),
            'response_type' => 'Assertion',
            'state' => $user -> user_id,
            'scope' => env( 'VSTS_APP_SCOPE' ),
            'redirect_uri' => env( 'VSTS_REDIRECT_URL' )

        ]);

        return urldecode( $url . "?" . $parameters );
    }


    public function storeToken( $user, $code )
    {
        $this -> sendTokenRequest( $user, 'urn:ietf:params:oauth:grant-type:jwt-bearer', $code );
    }


    public function updateUser( User $user, $refresh )
    {
        if ( !$this -> shouldUpdate( $user, $refresh ) )
        {
            return;
        }

        $this -> storeProfile( $user );
        $this -> storeAccounts( $user );
        $this -> storeProjects( $user );

        $user -> last_update = Carbon::now();
        $user -> save();
    }


    public function storeCommits( $user, VstsProject $vstsProject, $refresh )
    {
        if ( !$this -> shouldUpdate( $vstsProject, $refresh ) )
        {
            return;
        }

        $request = new Request( 'GET', 'https://' . $vstsProject -> account -> name . '.visualstudio.com/DefaultCollection/_apis/git/repositories?api-version=1.0' );
        $json = $this -> sendAuthRequest( $user, $request );
        $repositories = $json[ 'value' ];

        foreach ( $repositories as $repository )
        {
            $projectId = $repository[ 'project' ][ 'id' ];
            $url = $repository[ 'url' ];

            // Project does not exist in database, hence skip it
            if ( $projectId != $vstsProject -> project_id )
            {
                continue;
            }

            // Get all commits for a particular repository
            $request = new Request( 'GET', $url . '/commits?api-version=1.0&top=1000' );
            $json = $this -> sendAuthRequest( $user, $request );

            // Store each commit
            foreach ( $json[ 'value' ] as $commit )
            {
                // Commit is already stored
                if ( !is_null( $this -> commitRepo -> find( $commit[ 'commitId' ] ) ) )
                {
                    continue;
                }

                // Stores commit details
                $data = [];

                $data[ 'commit_id' ] = $commit[ 'commitId' ];
                $data[ 'project_id' ] = $projectId;
                $data[ 'author_email' ] = $commit[ 'author' ][ 'email' ];
                $data[ 'date' ] = $commit[ 'author' ][ 'date' ];
                $data[ 'comment' ] = $commit[ 'comment' ];
                $data[ 'web_url' ] = $commit[ 'remoteUrl' ];
                $data[ 'adds_counter' ] = key_exists( 'Add', $commit[ 'changeCounts' ] ) ? $commit[ 'changeCounts' ][ 'Add' ] : 0;
                $data[ 'edits_counter' ] = key_exists( 'Edit', $commit[ 'changeCounts' ] ) ? $commit[ 'changeCounts' ][ 'Edit' ] : 0;
                $data[ 'deletes_counter' ] = key_exists( 'Delete', $commit[ 'changeCounts' ] ) ? $commit[ 'changeCounts' ][ 'Delete' ] : 0;

                // Store new commit
                $this -> commitRepo -> create( $data );
            }
        }

        $vstsProject -> last_update = Carbon::now();
        $vstsProject -> save();
    }


    private function shouldUpdate( $model, $refresh, $refreshInterval = 5 )
    {
        $expireTime = Carbon::now() -> subMinute( $refreshInterval );
        return $refresh || is_null( $model -> last_update ) || $expireTime > $model -> last_update;
    }


    private function sendTokenRequest( $user, $grantType, $assertion )
    {
        $response = $this -> client -> request( 'POST', 'https://app.vssps.visualstudio.com/oauth2/token', [
            'form_params' => [
                'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
                'client_assertion' => env( 'VSTS_APP_SECRET' ),
                'grant_type' => $grantType,
                'assertion' => $assertion,
                'redirect_uri' => env( 'VSTS_REDIRECT_URL' )
            ]
        ] );

        $json = json_decode( $response -> getBody(), true );

        $user -> vsts_access_token = $json[ 'access_token' ];
        $user -> vsts_refresh_token = $json[ 'refresh_token' ];
        $user -> save();

        return $user;
    }


    private function refreshToken( $user )
    {
        return $this -> sendTokenRequest( $user, 'refresh_token', $user -> vsts_refresh_token );
    }


    private function sendAuthRequest( User $user, Request $request )
    {
        if ( is_null( $token = $user -> vsts_access_token ) )
        {
            return null;
        }

        $request = $request -> withHeader( 'Authorization', 'Bearer ' . $token );
        $response = $this -> client -> send( $request );

        // Request successful
        if ( $response -> getStatusCode() == 200 )
        {
            return json_decode( $response -> getBody(), true );
        }
        // Token expired, hence refresh token
        else if ( $response -> getStatusCode() == 203 )
        {
            $user = $this -> refreshToken( $user );
            return $this -> sendAuthRequest( $user, $request );
        }
        // Unhandled status code
        else
        {
            return null;
        }
    }


    private function storeProfile( User $user )
    {
        $request = new Request( 'GET', 'https://app.vssps.visualstudio.com/_apis/profile/profiles/me?api-version=1.0' );
        $json = $this -> sendAuthRequest( $user, $request );

        if ( is_null( $json ) || is_null( $id = $json[ 'id' ] ) || is_null( $email = $json[ 'emailAddress' ] ) )
        {
            return null;
        }

        $user -> vsts_profile_id = $id;
        $user -> vsts_email = $email;
        $user -> save();
    }


    private function storeAccounts( User $user )
    {
        if ( is_null( $profileId = $user -> vsts_profile_id ) )
        {
            return null;
        }

        $accounts = [];
        foreach ( [ 'ownerId', 'memberId' ] as $idType )
        {
            $request = new Request( 'GET', 'https://app.vssps.visualstudio.com/_apis/Accounts?' . $idType . '=' . $profileId . '&api-version=1.0' );
            $json = $this -> sendAuthRequest( $user, $request );
            $accounts[] = $json[ 'value' ];
        }

        // Owner accounts
        $ownerAccounts = $accounts[ 0 ];

        // Member accounts (includes owner accounts as well)
        $memberAccounts = $accounts[ 1 ];

        // Remove all accounts the user has access to
        $user -> accounts() -> detach();

        // Loop over all member accounts
        foreach ( $memberAccounts as $memberAccount )
        {
            // Get account if it already exists
            $vstsAccount = $this -> vstsAccountRepo -> find( $memberAccount[ 'accountId' ] );

            // Account does not exist, hence add it to database
            if ( is_null( $vstsAccount ) )
            {
                $vstsAccount = $this -> vstsAccountRepo -> create( [
                    'account_id' => $memberAccount[ 'accountId' ],
                    'name' => $memberAccount[ 'accountName' ]
                ] );
            }

            $isOwner = false;

            // Loop over all owner accounts
            foreach ( $ownerAccounts as $ownerAccount )
            {
                // Member is owner of the account
                if ( $ownerAccount[ 'accountId' ] == $memberAccount[ 'accountId' ] )
                {
                    $isOwner = true;
                    break;
                }
            }

            $user -> accounts() -> attach( $vstsAccount, [ 'is_owner' => $isOwner ] );
        }
    }


    private function storeProjects( User $user )
    {
        // Remove all projects the user has access to
        $user -> projects() -> detach();

        foreach ( $user -> accounts as $account )
        {
            $request = new Request( 'GET', 'https://' . $account -> name . '.visualstudio.com/DefaultCollection/_apis/projects?api-version=1.0' );
            $jsonArray = $this -> sendAuthRequest( $user, $request );
            $projectIds = [];

            foreach ( $jsonArray[ 'value' ] as $json )
            {
                // Ignore projects that are not ready
                if ( $json[ 'state' ] != 'wellFormed' )
                {
                    continue;
                }

                // Get project if it already exists in database
                $project = $this -> vstsProjectRepo -> find( $json[ 'id' ] );

                // Store each project id the user has access to
                $projectIds[] = $json[ 'id' ];

                // Project is new, hence store it in database
                if ( is_null( $project ) )
                {
                    $this -> vstsProjectRepo -> create( [
                        'project_id' => $json[ 'id' ],
                        'account_id' => $account -> account_id,
                        'name' => isset( $json[ 'name' ] ) ? $json[ 'name' ] : null,
                        'description' => isset( $json[ 'description' ] ) ? $json[ 'description' ] : null,
                        'revision' => $json[ 'revision' ]
                    ]);
                }
                // Project is outdated, hence update it
                else if ( $json[ 'revision' ] > $project -> revision )
                {
                    $this -> vstsProjectRepo -> update( [
                        'name' => isset( $json[ 'name' ] ) ? $json[ 'name' ] : null,
                        'description' => isset( $json[ 'description' ] ) ? $json[ 'description' ] : null,
                        'revision' => $json[ 'revision' ]
                    ]);
                }
            }

            // Add projects so that user has access to them
            $user -> projects() -> attach( $projectIds );
        }
    }
}