<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\S3\S3Client;  
use Aws\Exception\AwsException;

class S3Controller extends Controller {
    /**
     * creates a presigned url for uploading to s3
     * user must send the file name and type
     * @param Request $request
    */
    public function createPresignedUrl(Request $request){

        $this->validate($request, [
            'fileName' => 'required',
            'fileType' => 'required',
            'folder'   => 'required'
        ]);
        
        [ 
            'fileName' => $fileName, 
            'fileType' => $fileType, 
            'folder'   => $folder 
        ] = $request->only('fileName', 'fileType', 'folder');
                                
        $s3 = $this->getS3Instance();

        [ $file, $extension ] = explode('.', $fileName);
        $time = time() * 1000;
        try {
            // Upload data.
            $result = $s3->putObject([
                'Bucket'      => getenv('S3_BUCKET_NAME'),
                'Key'         => 'testing/' . $folder . '/' . $file .'_' . $time . '.' . $extension,
                'Expires'     => 60*60, 
                'ACL'         => 'public-read',
                'ContentType' => $fileType
            ]);

            // Print the URL to the object.
            return response()->json([
                'url' => $result["ObjectURL"]
            ]);
        } catch (S3Exception $e) {
            return $e->getMessage();
        }

    }

    public function deleteFile(Request $request) {
        try {
            $this->validate($request, [
                'key' => 'required'
            ]);
            
            $s3 = $this->getS3Instance();

            $s3->deleteObject([
                'Bucket' => getenv('S3_BUCKET_NAME'),
                'Key' => request('key')
            ]);

            return response()->json([ 'message' => 'success' ], 200);
        } catch(\Expection $e) {
            return response()->json([ 'message' => $e->getMessage() ], 400);
        }
    }

    private function getS3Instance() {
        return new S3Client([
            'version' => 'latest',
            'region'  => getenv('COGNITO_REGION')
        ]);
    }
}