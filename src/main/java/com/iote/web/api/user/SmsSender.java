
package com.iote.web.api.user;

import com.twilio.sdk.resource.instance.Account;
import com.twilio.sdk.TwilioRestClient;
import com.twilio.sdk.TwilioRestException;
import com.twilio.sdk.resource.factory.MessageFactory;
import com.twilio.sdk.resource.instance.Message;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;


public class SmsSender {

    /* Find your sid and token at twilio.com/user/account */
    public static final String ACCOUNT_SID = "AC123";
    public static final String AUTH_TOKEN = "456bef";

    public static void main(String[] args) throws TwilioRestException {

        TwilioRestClient client = new TwilioRestClient(ACCOUNT_SID, AUTH_TOKEN);

        Account account = client.getAccount();

        MessageFactory messageFactory = account.getMessageFactory();
        List<NameValuePair> params = new ArrayList<>();
        params.add(new BasicNameValuePair("To", "+14159352345")); // Replace with a valid phone number for your account.
        params.add(new BasicNameValuePair("From", "+14158141829")); // Replace with a valid phone number for your account.
        params.add(new BasicNameValuePair("Body", "Where's Wallace?"));
        Message sms = messageFactory.create(params);
    }
}