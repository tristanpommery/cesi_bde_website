'use strict';
module.exports = (sequelize, DataTypes) => {
  const user = sequelize.define('user', {
    underscored: true,
    promotion_id: DataTypes.INTEGER,
    email: DataTypes.STRING,
    roles: DataTypes.STRING,
    password: DataTypes.STRING,
    first_name: DataTypes.STRING,
    last_name: DataTypes.STRING,
    genre: DataTypes.STRING,
    image: DataTypes.STRING
  }, { underscored: true });
  user.associate = function(models) {
    // associations can be defined here
    models.user.hasMany(models.user_event);
  };
  return user;
};