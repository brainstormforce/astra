var wpCli = require('node-wp-cli');

beforeEach(() => {
    // cleanupWP();
});

afterEach(() => {
    // cleanupWP();
});


const cleanupWP = (() => {
    wpCli.call('option delete astra-settings', {  }, function(err, resp) {
        if (err) throw err;
    });

    wpCli.call('theme mod remove custom_logo', {  }, function(err, resp) {
        if (err) throw err;
    })

    wpCli.call('option delete site_title', {  }, function(err, resp) {
        if (err) throw err;
    })

    wpCli.call('option delete site_icon', {  }, function(err, resp) {
        if (err) throw err;
    })

    wpCli.call('option update blogdescription "Astra Test Enviornment"', {  }, function(err, resp) {
        if (err) throw err;
    })

    wpCli.call('post delete --all --force', {  }, function(err, resp) {
        if (err) throw err;
    })
});