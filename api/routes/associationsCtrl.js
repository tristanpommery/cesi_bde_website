var models = require('../models');

module.exports = {
    getAssociation: function (req, res) {

        models.association.findAll().then(function (asso) {
            if (asso) {
                res.status(201).json(asso);
            } else {
                res.status(404).json({ 'error': 'association not found' });
            }
        }).catch(function (err) {
            res.status(500).json({ 'error': 'cannot fetch association' });
        });
    }
}