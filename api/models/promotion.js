'use strict';
module.exports = (sequelize, DataTypes) => {
  const promotion = sequelize.define('promotion', {
    name: DataTypes.STRING
  }, {});
  promotion.associate = function(models) {
    // associations can be defined here
    models.promotion.hasMany(models.user);
  };
  return promotion;
};