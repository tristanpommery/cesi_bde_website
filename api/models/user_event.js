'use strict';
module.exports = (sequelize, DataTypes) => {
  const user_event = sequelize.define('user_event', {
    user_id: {
      type: DataTypes.INTEGER,
      references: 'user',
      referencesKey: 'id'
    },
    event_id: {
      type: DataTypes.INTEGER,
      references: 'event',
      referencesKey: 'id'
    }
  }, { 
    timestamps: false,
    underscored: true,
    freezeTableName: true,
    tableName: 'user_event'
   });
   user_event.removeAttribute('id');
  user_event.associate = function(models) {
    // associations can be defined here

    models.user_event.belongsTo(models.user);

    models.user_event.belongsTo(models.event);
  };
  return user_event;
};