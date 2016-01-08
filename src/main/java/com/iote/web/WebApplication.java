package com.iote.web;

import com.iote.web.api.Beacon;
import com.iote.web.auth.WebAuthorizer;
import com.iote.web.core.User;
import com.iote.web.auth.WebAuthenticator;
import com.iote.web.db.BeaconDao;
import com.iote.web.db.UserDao;
import io.dropwizard.Application;
import io.dropwizard.auth.AuthDynamicFeature;
import io.dropwizard.auth.basic.BasicCredentialAuthFilter;
import io.dropwizard.setup.Environment;
import com.iote.web.resources.UserResource;

public class WebApplication extends Application<WebConfiguration> {
    public static void main(String[] args) throws Exception {
        new WebApplication().run(args);
    }

    @Override
    public void run(WebConfiguration configuration, Environment environment) {
        final UserDao userDao = new UserDao(configuration);
        final BeaconDao beaconDao = new BeaconDao(configuration);

        environment.jersey().register(
                new AuthDynamicFeature(
                        new BasicCredentialAuthFilter.Builder<User>()
                                .setAuthenticator(new WebAuthenticator())
                                .setAuthorizer(new WebAuthorizer())
                                .setRealm("SUPER SECRET STUFF")
                                .buildAuthFilter()
                )
        );

        final UserResource userResource = new UserResource(userDao);
        environment.jersey().register(userResource);

    }
}