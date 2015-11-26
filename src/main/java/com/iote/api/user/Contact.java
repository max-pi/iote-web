package com.iote.api.user;

import lombok.Data;

@Data
public class Contact {

    private final String phone;
    private final String email;

    private final boolean allowSms;
    private final boolean confirmedSms;

    private final boolean allowEmails;
    private final boolean confirmedEmail;

}
