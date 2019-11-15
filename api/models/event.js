'use strict';
module.exports = (sequelize, DataTypes) => {
  const event = sequelize.define('event', {
    period_id: {
      type: DataTypes.INTEGER,
      references: 'period',
      referencesKey: 'id'
    },
    name: DataTypes.STRING,
    date: DataTypes.DATE,
    description: DataTypes.STRING,
    image: DataTypes.STRING,
    price: DataTypes.INTEGER,
    duration: DataTypes.STRING,
    localization: DataTypes.STRING
  }, {
      timestamps: false,
      underscored: true,
      freezeTableName: true,
      tableName: 'event'
  });
  event.associate = function(models) {
    // associations can be defined here
    models.event.hasMany(models.user_event, { onDelete: 'CASCADE' });

    models.event.belongsTo(models.period);
  };
  return event;
};