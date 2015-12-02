package com.iote.resources;

import com.mongodb.*;
import com.iote.api.User;
import com.google.common.base.Optional;
import com.codahale.metrics.annotation.Timed;
import java.net.UnknownHostException;

import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.Produces;
import javax.ws.rs.QueryParam;
import javax.ws.rs.core.MediaType;
import java.util.concurrent.atomic.AtomicLong;
import javax.ws.rs.FormParam;
import javax.ws.rs.POST;


@Path("/")
@Produces(MediaType.APPLICATION_JSON)
public class UserResource
{
    private final String template;
    private final String defaultName;
    private final AtomicLong counter;

    public UserResource(String template, String defaultName) 
                                  
    {
        this.template = template;
        this.defaultName = defaultName;
        this.counter = new AtomicLong();
        
    }
    
    private DB connectDB() throws UnknownHostException
    {
        MongoClient mongo = new MongoClient( "localhost" , 27017 );
        DB db = mongo.getDB("iote");
        return db;
    }

    @GET
    @Timed
    public User getUser(@QueryParam("name") Optional<String> name) 
                                                   throws UnknownHostException 
    {
        DB db = connectDB();
        String temp = db.getCollectionNames().toString();
        return User.builder().username(temp).build();
    }
    
}