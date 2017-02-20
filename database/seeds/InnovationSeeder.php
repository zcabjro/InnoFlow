<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Innovation;
use App\Services\Common\Helper;

class InnovationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $directory = __DIR__ . "/code_examples";
        $files = new FilesystemIterator( $directory, FilesystemIterator::SKIP_DOTS );
        $total = iterator_count( $files );

        $users = User::all();

        foreach ( $users as $user )
        {
            $randomTotal = rand ( 0, $total );
            $randomIndices = Helper::uniqueRandomNumbersWithinRange( 1, $total, $randomTotal );

            foreach ( $randomIndices as $index )
            {
                $fileName = $directory . '/example' . $index . '.txt';

                $file = fopen( $fileName, 'r' );

                $innovation = new Innovation();
                $innovation -> code = fread( $file, filesize( $fileName ) );
                $innovation -> user() -> associate( $user );
                $innovation -> save();

                fclose( $file );
            }
        }
    }
}
