'use strict';
module.exports = (sequelize, DataTypes) => {
    const gallery = sequelize.define('gallery', {
        id: {
            type: DataTypes.UUID,
            primaryKey: true,
            defaultValue: DataTypes.UUIDV4,
            allowNull: false,
            autoIncrement: false,
        },
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
        models.gallery.belongsToMany(models.user, { through: 'user_gallery' }, { onDelete: 'CASCADE' });

        models.gallery.belongsTo(models.event);

    };
    return gallery;
};