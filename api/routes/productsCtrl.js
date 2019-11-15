var models = require('../models');

module.exports = {
    getProduct: function (req, res) {

        models.product.findAll({
            include: [{
                model: models.category
            }],
            attributes: ['id', 'name', 'price', 'image', 'stock', 'name']
        }).then(function (product) {
            if (product) {
                res.status(201).json(product);
            } else {
                res.status(404).json({ 'error': 'product not found' });
            }
        }).catch(function (err) {
            res.status(500).json({ 'error': 'cannot fetch product' });
        });
    }
}