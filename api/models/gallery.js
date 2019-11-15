'use strict';
module.exports = (sequelize, DataTypes) => {
    const gallery = sequelize.define('gallery', {
        event_id: {
            type: DataTypes.INTEGER,
            references: 'event',
            referencesKey: 'id'
        },
        author_id: {
            type: DataTypes.INTEGER,
            references: 'user',
            referencesKey: 'id'
        },
        image: DataTypes.STRING
    }, {
        timestamps: false,
        underscored: true,
        freezeTableName: true,
        tableName: 'gallery'
    });
    gallery.associate = function (models) {
        // associations can be defined here
        models.gallery.hasMany(models.user_gallery, { onDelete: 'CASCADE' });

        models.gallery.belongsTo(models.event);

        models.gallery.belongsTo(models.user);
    };
    return gallery;
};