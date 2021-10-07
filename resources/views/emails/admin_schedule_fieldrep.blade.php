<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f7f7f7" >
    <tbody>
        <tr>
            <td style="text-align:left">
                <table style="max-width:700px;margin: 0 auto; padding-top: 30px;" class="m_7514756023630913654m_8194902932891523857widthFull-" width="700" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td style="background-color:#3C8DBC;vertical-align:top;text-align:center" bgcolor="#000000">
                                <table cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tbody>
                                        <tr>
                                            <td class="m_7514756023630913654m_8194902932891523857height75-" height="85">
                                                <a href="javascript:void(0)">
                                                    <img src="{{ AppHelper::APP_URL.AppHelper::LOGO_WHITE }}" alt="Alpha Rep Service" title="Alpha Rep Service" style="display:block" class="CToWUd" width="124" height="62" border="0">
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="max-width:700px; margin: 0 auto; padding-bottom: 30px;" class="m_7514756023630913654m_8194902932891523857widthFull-" width="700" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td style="padding:26px 40px 35px 40px" class="m_7514756023630913654m_8194902932891523857mainStory-" bgcolor="#ffffff">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tbody>
                                        <tr>
                                            <td style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:25px;line-height:30px;color:#000000;font-weight:bold;text-align:center" class="m_7514756023630913654m_8194902932891523857headline-">
                                             Service Call
                                         </td>
                                     </tr>
                                     <tr>
                                        <td style="font-size:1px;line-height:10px" class="m_7514756023630913654m_8194902932891523857height14-" height="10">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:16px;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                            Dear {{ @$fieldrep->first_name}}, 

                                            <p>You have been scheduled for a service call at the following location:</p>

                                            <p> [{{ @$details['site_code'] }}]-{{ @$details['location'] }}</p>
                                        
                                            <p>You can obtain detailed information about the assignment by logging on to <a href="{{ AppHelper::APP_URL }}">{{ AppHelper::APP_URL }}</a></p>

                                            <p>Tracking information  for your shipment will be sent in a separate email. We recommend that you do not visit the store until the shipment has arrived, as we have no knowledge of exact delivery times. </p>

                                            <p>Please allow enough time to complete your survey and your photos before the expiration of the service round.</p> 

                                            Thank you. <br>
                                            Alpha Rep Service
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