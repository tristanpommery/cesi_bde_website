'use strict';
module.exports = (sequelize, DataTypes) => {
  const product = sequelize.define('product', {
    category_id: DataTypes.INTEGER,
    association_id: DataTypes.INTEGER,
    name: DataTypes.STRING,
    description: DataTypes.STRING,
    price: DataTypes.INTEGER,
    stock: DataTypes.INTEGER,
    image: DataTypes.STRING
  }, {});
  product.associate = function(models) {
    // associations can be defined here
  };
  return product;
};