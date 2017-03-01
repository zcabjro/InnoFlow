export default function(error) {
  return new HttpErrorHelper(error);
}

function HttpErrorHelper(error) {
  this.error = error;
  this.payload = processError(error);  
}

function processError(error) {
  return error.response ? error.response.data : error.message;
}