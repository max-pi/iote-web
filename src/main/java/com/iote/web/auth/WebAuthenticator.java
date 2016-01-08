package com.iote.web.auth;

import com.google.common.base.Optional;
import com.iote.web.core.User;
import io.dropwizard.auth.AuthenticationException;
import io.dropwizard.auth.Authenticator;
import io.dropwizard.auth.basic.BasicCredentials;

public class WebAuthenticator implements Authenticator<BasicCredentials, User> {

    @Override
    public Optional<User> authenticate(BasicCredentials credentials) throws AuthenticationException {

        if ("crimson".equals(credentials.getPassword())) {
            return Optional.of(User.builder().build());
        }
        return Optional.absent();
    }

}