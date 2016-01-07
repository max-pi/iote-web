package com.iote;

import com.mongodb.DB;
import com.mongodb.MongoClient;
import io.dropwizard.Application;
import io.dropwizard.setup.Bootstrap;
import io.dropwizard.setup.Environment;
import com.iote.resources.UserResource;

import java.net.UnknownHostException;

public class WebApplication extends Application<WebConfiguration> {
    public static void main(String[] args) throws Exception {
        new WebApplication().run(args);
    }

    @Override
    public void run(WebConfiguration configuration, Environment environment) {
        try {
            MongoClient mongo = new MongoClient(configuration.getHostname(), configuration.getPort());
            DB mongoDb = mongo.getDB(configuration.getDatabase());

            final UserResource userResource = new UserResource(mongoDb);
            environment.jersey().register(userResource);

        } catch (Exception e) {
            // should terminate when this happens
        }
    }
}