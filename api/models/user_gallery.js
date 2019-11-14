'use strict';
module.exports = (sequelize, DataTypes) => {
    const user_gallery = sequelize.define('user_gallery', {
        user_id: {
            type: DataTypes.INTEGER,
            references: 'user',
            referencesKey: 'id'
        },
        gallery_id: {
            type: DataTypes.INTEGER,
            references: 'gallery',
            referencesKey: 'id'
        }
    }, {
        timestamps: false,
        underscored: true,
        freezeTableName: true,
        tableName: 'user_gallery'
    });
    user_gallery.removeAttribute('id');
    user_gallery.associate = function (models) {
        // associations can be defined here

        models.user_gallery.belongsTo(models.user);

        models.user_gallery.belongsTo(models.gallery);
    };
    return user_gallery;
};