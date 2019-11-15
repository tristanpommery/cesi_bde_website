'use strict';
module.exports = (sequelize, DataTypes) => {
    const category = sequelize.define('category', {
        name: DataTypes.STRING
    }, {
        timestamps: false,
        underscored: true,
        freezeTableName: true,
        tableName: 'category'
    });
    category.associate = function (models) {
        // associations can be defined here
        models.category.hasMany(models.product, { onDelete: 'CASCADE' });
    };
    return category;
};