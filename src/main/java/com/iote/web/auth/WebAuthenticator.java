package com.iote.web.auth;

import com.google.common.base.Optional;
import com.iote.web.core.User;
import com.mongodb.BasicDBObject;
import com.mongodb.DB;
import io.dropwizard.auth.AuthenticationException;
import io.dropwizard.auth.Authenticator;
import io.dropwizard.auth.basic.BasicCredentials;
import org.mongojack.JacksonDBCollection;

public class WebAuthenticator implements Authenticator<BasicCredentials, User> {
    private final DB mongod;
    public WebAuthenticator(DB mongod) {
        this.mongod = mongod;
    }

    @Override
    public Optional<User> authenticate(BasicCredentials credentials) throws AuthenticationException {
        System.out.println(credentials.getUsername());
        System.out.println(credentials.getPassword());

        JacksonDBCollection<User, String> usersCollection = JacksonDBCollection.wrap(
                mongod.getCollection("users"),
                User.class, String.class);

        BasicDBObject query = new BasicDBObject("emails", credentials.getUsername());
        User userObject = usersCollection.findOne(query);

        System.out.println(userObject.getPassword());

        if (userObject != null) {
            if (userObject.getPassword().equals(credentials.getPassword())) {
                return Optional.of(userObject);
            }
        }

        return Optional.absent();
    }

}