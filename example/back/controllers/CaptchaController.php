<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use visualCaptcha\Captcha;
use visualCaptcha\SessionCaptcha;

class CaptchaController extends Controller {

    /**
     * Generate a Captcha
     * @return Response
     */
    public function start($howmany)
    {
        $session = new SessionCaptcha();
        $captcha = new Captcha($session);
        $captcha->generate($howmany);

        return response()->json($captcha->getFrontEndData());
    }

    /**
     * Get an audio file
     * @param  string $type Song type (mp3/ogg)
     * @return File
     */
    public function audio($type = 'mp3')
    {
        $session = new SessionCaptcha();
        $captcha = new Captcha($session);

        return $captcha->streamAudio(array(), $type);
    }

    /**
     * Get an image file
     * @param  string $index Image index
     * @return File
     */
    public function image($index)
    {
        $session = new SessionCaptcha();
        $captcha = new Captcha($session);

        return $captcha->streamImage(array(), $index, 0 );
    }

}
