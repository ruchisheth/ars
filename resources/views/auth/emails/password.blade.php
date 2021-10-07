<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
    <tbody>
        <tr>
            <td style="text-align:left">
                <table style="margin: 0 auto;" class="m_7514756023630913654m_8194902932891523857widthFull-" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td style="background-color:#3980A3;vertical-align:top;text-align:center" bgcolor="#000000">
                                <table cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tbody>
                                        <tr>
                                            <td class="m_7514756023630913654m_8194902932891523857height75-" height="auto">
                                                <a href="javascript:void(0)">
                                                    <img src="{{ config('app.url').AppHelper::LOGO_WHITE }}" alt="Alpha Rep Service" title="Alpha Rep Service" style="display:block; padding: 6px 0;" class="CToWUd" width="auto" height="50" border="0">
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="margin: 0 auto; border-bottom: 1px solid #e5e5e5; border-left: 1px solid #e5e5e5; border-right: 1px solid #e5e5e5" class="m_7514756023630913654m_8194902932891523857widthFull-" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td style="padding:25px 20px 25px 20px" class="m_7514756023630913654m_8194902932891523857mainStory-" bgcolor="#ffffff">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                     
                                        <tr>
                                            <td style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:13px;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                                Hello,


                                                <p>There is no reason to fret if you have forgotten your ARS password. Here is a simple one click solution for password reset.</p>

                                                <p>Click the link below to take you to the create a new password page. </p>

                                                <p><a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}">Reset Password </a></p>


                                                <p>If you have not made any password reset request,it is likely that another user entered your email address by mistake and you can simply disregard this email.
                                                    For further help or clarifications,please contact support@alpharepservice.com</p>

                                                    Thank you. <br>
                                                    Alpha Rep Services
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>