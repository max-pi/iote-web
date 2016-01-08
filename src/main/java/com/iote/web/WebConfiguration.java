package com.iote.web;

import io.dropwizard.Configuration;
import com.fasterxml.jackson.annotation.JsonProperty;
import lombok.Data;
import org.hibernate.validator.constraints.NotEmpty;

@Data
public class WebConfiguration extends Configuration {

    private String hostname;
    private String database;
    private Integer port;

}