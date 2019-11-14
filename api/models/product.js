'use strict';
module.exports = (sequelize, DataTypes) => {
  const product = sequelize.define('product', {
    category_id: {
      type: DataTypes.INTEGER,
      references: 'category',
      referencesKey: 'id'
    },
    association_id: {
      type: DataTypes.INTEGER,
      references: 'association',
      referencesKey: 'id'
    },
    name: DataTypes.STRING,
    description: DataTypes.STRING,
    price: DataTypes.INTEGER,
    stock: DataTypes.INTEGER,
    image: DataTypes.STRING,
    sold_count: DataTypes.INTEGER
  }, {
    underscored: true,
    freezeTableName: true,
    tableName: 'product',
    timestamps: false});
  product.associate = function(models) {
    // associations can be defined here
    models.product.belongsTo(models.category);

    models.product.belongsTo(models.association);

    models.product.hasMany(models.user, { onDelete: 'CASCADE' });

    models.product.hasMany(models.comment, { onDelete: 'CASCADE' });
  };
  return product;
};