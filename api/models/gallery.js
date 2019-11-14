'use strict';
module.exports = (sequelize, DataTypes) => {
    const gallery = sequelize.define('gallery', {
        event_id: DataTypes.INTEGER,
        image: DataTypes.STRING
    }, {
        timestamps: false,
        underscored: true,
        freezeTableName: true,
        tableName: 'gallery'
    });
    gallery.associate = function (models) {
        // associations can be defined here
        models.gallery.hasMany(models.user_gallery);
    };
    return gallery;
};