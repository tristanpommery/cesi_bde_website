var models = require('../models');

module.exports = {
    getEvent: function (req, res) {

        models.event.findAll({
            include: [{
                model: models.period,
                attributes: ['time']
            }]
        }).then(function (event) {
            if (event) {
                res.status(201).json(event);
            } else {
                res.status(404).json({ 'error': 'event not found' });
            }
        }).catch(function (err) {
            res.status(500).json({ 'error': 'cannot fetch event' });
        });
    }
}