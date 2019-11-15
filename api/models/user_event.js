'use strict';
module.exports = (sequelize, DataTypes) => {
  const user_event = sequelize.define('user_event', {
    user_id: {
      type: DataTypes.INTEGER,
      primaryKey: true
    },
    event_id: {
      type: DataTypes.INTEGER,
      primaryKey: true
    }
  }, { 
    timestamps: false,
    underscored: true,
    freezeTableName: true,
    tableName: 'user_event'
   });
  user_event.associate = function(models) {
    // associations can be defined here
  };
  return user_event;
};