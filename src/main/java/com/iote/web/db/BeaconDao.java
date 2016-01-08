package com.iote.web.db;

import com.iote.web.WebConfiguration;
import com.mongodb.DB;
import com.mongodb.MongoClient;

public class BeaconDao {
    private DB database;
    public BeaconDao(WebConfiguration configuration) {
        try {
            MongoClient mongo = new MongoClient(configuration.getHostname(), configuration.getPort());
            this.database = mongo.getDB(configuration.getDatabase());
        } catch (Exception e) {
            // do nothing for now
        }
    }

}
