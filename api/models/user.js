'use strict';
module.exports = (sequelize, DataTypes) => {
  const user = sequelize.define('user', {
    promotion_id: DataTypes.INTEGER,
    campus_id: DataTypes.INTEGER,
    email: DataTypes.STRING,
    roles: DataTypes.STRING,
    password: DataTypes.STRING,
    first_name: DataTypes.STRING,
    last_name: DataTypes.STRING,
    genre: DataTypes.STRING,
    image: DataTypes.STRING
  }, { 
      underscored: true,
      freezeTableName: true,
      tableName: 'user',
      timestamps: false
   });
  user.associate = function(models) {
    // associations can be defined here
    models.user.hasMany(models.user_event);

    models.user.belongsTo(models.campus);

    models.user.belongsTo(models.promotion);

    models.user.belongsTo(models.association);

  };
  return user;
};