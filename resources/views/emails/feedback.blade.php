<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f7f7f7">
  <tbody>
    <tr>
      <td style="text-align:center">
        <table style="margin: 0 auto;" class="m_7514756023630913654m_8194902932891523857widthFull-" width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td style="background-color:#3C8DBC;vertical-align:top;text-align:center" bgcolor="#000000">
                <table cellspacing="0" cellpadding="0" border="0" align="center">
                  <tbody>
                    <tr>
                      <td class="m_7514756023630913654m_8194902932891523857height75-" height="auto">
                        <a href="javascript:void(0)">
                          <img src="{{ AppHelper::APP_URL.AppHelper::LOGO_WHITE }}" alt="Alpha Rep Service" title="Alpha Rep Service" style="display:block; padding: 6px 0;" class="CToWUd" width="auto" height="50" border="0">
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
                      <td style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:25px;line-height:30px;color:#000000;font-weight:bold;text-align:center" class="m_7514756023630913654m_8194902932891523857headline-">
                      You Have a New Feedback!
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:1px;line-height:10px" class="m_7514756023630913654m_8194902932891523857height14-" height="10">&nbsp;</td>
                    </tr>
                    {{-- <tr>
                      <td style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;text-align:center" class="m_7514756023630913654m_8194902932891523857body-">
                        Your account has been created  successfully by <b>{{ @$details['client_name'] }}!</b>
                      </td>
                    </tr>
                    <tr>
                      <td style=" padding:0 0 10px 0; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;text-align:center" class="m_7514756023630913654m_8194902932891523857body-">
                        Here are your login details
                      </td>
                    </tr> --}}
                    <tr>
                      <td>
                        <table style="" cellspacing="5" cellpadding="0" border="0" align="center">{{-- margin-left:25% --}}
                          <tbody>
                            <tr>
                              <td style="text-align:right;vertical-align:top;width:25%;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                <b>Name:</b> 
                              </td>
                              <td style="text-align:left;padding:0 0 0 15px; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                {{ @$name  }}
                              </td>
                            </tr>
                            @if(@$phone_number != "")
                            <tr>
                              <td style="text-align:right;vertical-align:top;width:25%;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                <b>Phone Number:</b> 
                              </td>
                              <td style="text-align:left;padding:0 0 0 15px; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                {{ @$phone_number  }}
                              </td>
                            </tr>
                            @endif
                            <tr>
                              <td style="text-align:right;vertical-align:top;width:25%;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                <b>Client:</b> 
                              </td>
                              <td style="text-align:left;padding:0 0 0 15px; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                {{ @$client_name  }}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align:right;vertical-align:top;width:25%;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                <b>Store:</b> 
                              </td>
                              <td style="text-align:left;padding:0 0 0 15px; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                {{ @$site }}
                              </td>
                            </tr>
                            <tr>
                              <td style="text-align:right;vertical-align:top;width:25%;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                <b>Feedback:</b> 
                              </td>
                              <td style="text-align:justify;padding:0 0 0 15px; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:26px;color:#666666;" class="m_7514756023630913654m_8194902932891523857body-">
                                {{ @$feedback }}
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td class="m_7514756023630913654m_8194902932891523857height24-" height="23">&nbsp;</td>
                    </tr>
                    {{-- <tr>
                      <td style="vertical-align:top;text-align:center">
                        <table style="min-width:175px" cellspacing="0" cellpadding="0" border="0" bgcolor="#4cb7ff" align="center">
                          <tbody>
                            <tr>
                              <td style="background-color:#3C8DBC;padding:10px 20px 10px 20px;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;line-height:22px;color:#ffffff;text-align:center" class="m_7514756023630913654m_8194902932891523857body-">
                                <a href="{{ AppHelper::APP_URL  }}" name="m_7514756023630913654_m_8194902932891523857_CTA link" style="color:#ffffff;text-decoration:none" class="m_7514756023630913654m_8194902932891523857noDecoration-" target="_blank"><strong>Get Into Alpha Rep Service!</strong></a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr> --}}
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