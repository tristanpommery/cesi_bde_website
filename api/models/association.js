'use strict';
module.exports = (sequelize, DataTypes) => {
    const association = sequelize.define('association', {
        name: DataTypes.STRING,
        description: DataTypes.STRING,
        image: DataTypes.STRING
    }, {
        timestamps: false,
        underscored: true,
        freezeTableName: true,
        tableName: 'association'
    });
    association.associate = function (models) {
        // associations can be defined here
        models.association.hasMany(models.user, { onDelete: 'CASCADE' });
    };
    return association;
};