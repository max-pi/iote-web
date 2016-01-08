package com.iote.web.db;

import com.iote.web.WebConfiguration;
import com.mongodb.DB;
import com.mongodb.MongoClient;

public class UserDao {
    private DB database;
    public UserDao(WebConfiguration configuration) {
        try {
            MongoClient mongo = new MongoClient(configuration.getHostname(), configuration.getPort());
            this.database = mongo.getDB(configuration.getDatabase());
        } catch (Exception e) {
            // do nothing for now
        }
    }
}
