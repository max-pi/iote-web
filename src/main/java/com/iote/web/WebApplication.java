package com.iote.web;

import com.iote.web.core.User;
import com.iote.web.auth.WebAuthenticator;
import com.mongodb.DB;
import com.mongodb.MongoClient;
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
        try {
            MongoClient mongo = new MongoClient(configuration.getHostname(), configuration.getPort());
            DB mongoDb = mongo.getDB(configuration.getDatabase());

            environment.jersey().register(
                    new AuthDynamicFeature(
                          new BasicCredentialAuthFilter.Builder<User>()
                                  .setAuthenticator(new WebAuthenticator())
                                  .buildAuthFilter()
                    )
            );

            final UserResource userResource = new UserResource(mongoDb);
            environment.jersey().register(userResource);

        } catch (Exception e) {
            // should terminate when this happens
        }
    }
}