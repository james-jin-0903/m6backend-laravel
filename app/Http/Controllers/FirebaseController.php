<?php
namespace App\Http\Controllers;
use \Firebase\JWT\JWT;

class FirebaseController extends Controller {

    public function getToken() {
        try {
            $token = $this->generateToken();
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Successful',
            'token' => $token
        ], 200);
    }

    private function getServiceAccount(){
        $settings = array();
        $settings['service_account_email'] = env('FIREBASE_SERVICE_ACCOUNT');
        $settings['private_key'] = env('FIREBASE_PRIVATE_KEY');
        $settings['uid'] = env('FIREBASE_UID');

//      Production
//        $settings['service_account_email'] = "firebase-adminsdk-iah1w@m6connect-ffedb.iam.gserviceaccount.com";
//        $settings['private_key'] = "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC7o25PZgVRnbzV\nuT0aO7DYaqr/YSufIeEAM9/zG8acAJlRujsCz5oM9hq7MqyWNIk3y+wLZE8fv/dh\n9qkHq3H/87Ac2Q0WlNG5LktBLmD/RcquRuWr7wJxJwG7Vd9eV7Hk7hWwui0Y4M52\n5pDQLuMcWhn5NcoW+fakF4bSI66R2T6z0fMJOUMj15Js61nSvNiIi2KYcTQVWrqr\nVZ9yhTOd7jQC3YBDHSegF840LGbpB9wKsnqRnKFz0j2dc9p2LjYTiuJ15PkGuyb2\nWa733K6TbhYDAAN6s4BMMnTmTbNnEfixHbVbksP73xT0dgtGdrkAY8RRYO/DIbPs\nDM6u7Y4DAgMBAAECggEAXTDVJ3/u4lMqJyeh7jZ5JXMMzFtRU+56liQtdMt1v6MY\ngpuwlTvQMeHE8vY++zbKc7jG2f2OwqEP10qvNozyyIEaFanDM1TkOEmCvLOxHvdl\nq1qw1MyW9OW/mXATyQJa6u1IPoaowTNdt3XZtFulFj61qa0KWpD46xR4NEPefraB\nhab6p9FRpP6eiwqr+G891H1gkBySYa+6z5citPjFj4ma7op9WDxJrOosVAVZaUcd\nxcri19XS1LQncn6BHYX18U77gk13KLm5QlmyjEA7r9TEJe6WiOwacybVTokPEZlh\n0yNCGWQpIDAobQkFfBbE8HFkUpCMQL6uF35GQ8WFwQKBgQDriZIOrsmnRbOxyg8Y\n9/5qK4jTYABu5vnSkMiThxdRCv2pn6Svf2UXoYo+n1Z+nd1ynWP48zPIVMgmDSIT\nmAPTJ2HvmjHvHpXdAqmuSTQU/fp8J3jTAC+eAWeNrXLB/Fpp75MPbjqx4Xzu4T61\nTI51qDiLhT3j2qiUSJ2nlnrD4wKBgQDL8JI0khbhIgV9kgsM5aKkSc9LZ27m/7jb\nln83qg8VTyAu9oH4J6nLw2ir+wlpjRAaUCFE8yqHKcJ2tdAFpYVRke65myyxH9za\nDBPRqlGc4PXizvIwnbrbmtdLgbLXmTo7fAeNJ6lUGJiF4L4Ve1c1G2KP81ZgF+xx\nRMBsoVNnYQKBgQCCsNbGJt3lXpuSbtdpt1EHgBhvkLks0CSWXZetpCuf4EVXtSkf\n58QkFJIspSTNJLMXQZWTMP7ujXxxAKCD1rlolFWXKSvDzmjBNRUJi7zGWRZ/hTdZ\nfKTvO0BGMJOYZ3mThkdHLJaM1N0JfxnYZx400p5tbAUnRNB9Vr2Dm8+KmQKBgAG6\n7MKdaqxGSZ2jG4zkddjG94dAPWSgTsUuJa4nNzGLFk7PsJKWY45i/gRXgODwXW6b\nt2yKU5YJ+KhhKn6hxNvITSot8tcd2hXPfGuO8lpOXkeqUcuvyuIKEkGFvCqwFhpj\nFv1PlNQt0T1xhqtP/JFqxg9KlTUDfvIHD0rco4SBAoGACUXZmPVxZQnm3p0I4E0F\n3YR7t/MjGHJwS2dh98oU2UiuY/SnA5kgWUWxkcecWR1VseIsiIH7UWbuO1wbGN74\n01McmiN2u0L95sLUQkrabq6VqYB3LSbxawxqAcNTa3RiLjPyINsmzN7BsW1GbqxV\nLErjvQXIkFp/bIgJC6zKNoE=\n-----END PRIVATE KEY-----\n";
//        $settings['uid'] = "Fnmv2TByDbQDj984QgGa9z9aclh2";

        return $settings;
    }

    private function generateToken(){
        $now_seconds = time();
        $settings = $this->getServiceAccount();

        $payload = array(
            "iss" => $settings['service_account_email'],
            "sub" => $settings['service_account_email'],
            "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
            "iat" => $now_seconds,
            "exp" => $now_seconds+(60*60),  // Maximum expiration time is one hour
            "uid" => $settings['uid']
        );
        $private_key = $settings['private_key'];
        return JWT::encode($payload, $private_key, "RS256");
    }

}


