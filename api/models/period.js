'use strict';
module.exports = (sequelize, DataTypes) => {
    const period = sequelize.define('period', {
        time: DataTypes.STRING
    }, {
        timestamps: false,
        underscored: true,
        freezeTableName: true,
        tableName: 'period'
    });
    period.associate = function (models) {
        // associations can be defined here
        models.period.hasMany(models.event);
    };
    return period;
};