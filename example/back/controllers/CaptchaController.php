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

    /**
     * Check if the Captcha result is good
     * @return Mixed
     */
    public function send(Request $request)
    {

        $data = $request->all();

        //Validation rules
        $rules = array(

            'name' => 'required'
        );

        //Validate data
        $validator = Validator::make($data, $rules);

        //If everything is correct than run passes.
        if ($validator->passes())
        {
            $session = new SessionCaptcha();
            $captcha = new Captcha($session);

            $frontendData = $captcha->getFrontendData();

            if (!$frontendData)
            {
                return Redirect::back()->withInput()->withErrors(array('message' => Lang::get('text.captcha.none')));

            } else
            {
                // If an image field name was submitted, try to validate it
                if ($imageAnswer = $request->input($frontendData['imageFieldName']))
                {
                    if ($captcha->validateImage($imageAnswer))
                    {
                        // Return true if the result is correct

// DO ACTION HERE, ex send email, update db...

                        // This return back to form to show sucess, thats why i use status=1
                        return Redirect::back()->with('status', 1);

                    } else
                    {
                        return Redirect::back()->withInput()->withErrors(array('message' => Lang::get('text.captcha.image')));

                    }
                } else if ($audioAnswer = $request->input($frontendData['audioFieldName']))
                {
                    if ($captcha->validateAudio($audioAnswer))
                    {
                        // Return true if the result is correct

                        // DO ACTION HERE, ex send email, update db...


                        // This return back to form to show sucess, thats why i use status=1
                        return Redirect::back()->with('status', 1);

                    } else
                    {
                        return Redirect::back()->withInput()->withErrors(array('message' => Lang::get('text.captcha.audio')));

                    }
                } else
                {
                    return Redirect::back()->withInput()->withErrors(array('message' => Lang::get('text.captcha.incomplete')));

                }
            }

        } else
        {
            //return "contact form with errors";
            return Redirect::back()->withErrors($validator)->with('status', 2);;

        }

    }

}
