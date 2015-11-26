package com.iote;

import io.dropwizard.Application;
import io.dropwizard.setup.Bootstrap;
import io.dropwizard.setup.Environment;
import com.iote.resources.HelloWorldResource;
import com.iote.health.TemplateHealthCheck;

public class WebApplication extends Application<WebConfiguration> {
    public static void main(String[] args) throws Exception {
        new WebApplication().run(args);
    }

    @Override
    public String getName() {
        return "hello-world";
    }

    @Override
    public void initialize(Bootstrap<WebConfiguration> bootstrap) {
        // nothing to do yet
    }

    @Override
    public void run(WebConfiguration configuration,
                    Environment environment) {
        final HelloWorldResource resource = new HelloWorldResource(
                configuration.getTemplate(),
                this.getName()
        );
        final TemplateHealthCheck healthCheck =
                new TemplateHealthCheck(configuration.getTemplate());
        environment.healthChecks().register("template", healthCheck);
        environment.jersey().register(resource);
        System.out.print("sukana");
    }

}