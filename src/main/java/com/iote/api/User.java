package com.iote.api;

import lombok.Data;

import com.iote.api.user.Contact;
import com.iote.api.user.Registration;
import com.iote.api.user.Session;

import java.util.ArrayList;

@Data
public class User {

    private final String username;
    private final String password;

    private final String name;
    private final String zipcode;

    private final String role;
    private final boolean active;

    private final Application application;
    private final Contact contact;

    private final Registration registration;
    private final String resetPasswordToken;

    private final ArrayList<Log> updated;
    private final ArrayList<Beacon> beacons;
    private final ArrayList<Session> sessions;

}
