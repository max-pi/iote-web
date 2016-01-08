package com.iote.web.auth;

import com.iote.web.core.User;
import io.dropwizard.auth.Authorizer;

public class WebAuthorizer implements Authorizer<User> {

    @Override
    public boolean authorize(User user, String role) {
        if (role.equals("ADMIN") && !user.getName().startsWith("chief")) {
            return false;
        }
        return true;
    }
}