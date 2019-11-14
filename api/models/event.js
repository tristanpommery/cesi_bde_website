'use strict';
module.exports = (sequelize, DataTypes) => {
  const event = sequelize.define('event', {
    period_id: DataTypes.INTEGER,
    name: DataTypes.STRING,
    date: DataTypes.DATE,
    description: DataTypes.STRING,
    image: DataTypes.STRING,
    price: DataTypes.INTEGER,
    duration: DataTypes.STRING,
    localization: DataTypes.STRING
  }, { underscored: true });
  event.associate = function(models) {
    // associations can be defined here
    models.user.hasMany(models.user_event);
  };
  return event;
};