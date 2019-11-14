'use strict';
module.exports = (sequelize, DataTypes) => {
  const user_event = sequelize.define('user_event', {
    underscored: true,
    user_id: {
      type: DataTypes.INTEGER,
    },
    event_id: {
      type: DataTypes.INTEGER,
    }
  }, { underscored: true });
  user_event.associate = function(models) {
    // associations can be defined here

    models.user_event.belongsTo(models.user);

    models.user_event.belongsTo(models.event);
  };
  return user_event;
};