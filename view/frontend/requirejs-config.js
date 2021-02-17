let config = {
    map: {
        '*': {
            mercuryApp: 'Mercury_Payment/js/app'
        }
    },
    paths: {
        'kjua': 'Mercury_Payment/js/kjua.min'
    },
    shim: {
        "kjua": ["jquery"]
    }
};
