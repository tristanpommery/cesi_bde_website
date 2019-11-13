'use strict';
module.exports = (sequelize, DataTypes) => {
  const user = sequelize.define('user', {
    first_name: DataTypes.STRING,
    last_name: DataTypes.STRING,
    genre: DataTypes.STRING,
    email: DataTypes.STRING,
    password: DataTypes.STRING,
    roles: DataTypes.STRING,
    image: DataTypes.STRING
  }, {});
  user.associate = function(models) {
    // associations can be defined here
    models.user.belongsTo(models.campus);

    models.user.belongsTo(models.promotion);
  };
  return user;
};