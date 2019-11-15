'use strict';
module.exports = (sequelize, DataTypes) => {
  const user = sequelize.define('user', {
    id: {
      type: DataTypes.UUID,
      primaryKey: true,
      defaultValue: DataTypes.UUIDV4,
      allowNull: false,
      autoIncrement: false,
    },
    association_id: {
      type: DataTypes.INTEGER,
      references: 'association',
      referencesKey: 'id'
    },
    promotion_id: {
      type: DataTypes.INTEGER,
      references: 'promotion',
      referencesKey: 'id'
    },
    campus_id: {
      type: DataTypes.INTEGER,
      references: 'user',
      referencesKey: 'id'
    },
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
    models.user.belongsToMany(models.event, {through: 'user_event'}, { onDelete: 'CASCADE' });
    models.user.belongsToMany(models.gallery, { through: 'user_gallery' }, { onDelete: 'CASCADE' });

    models.user.belongsTo(models.campus);

    models.user.belongsTo(models.promotion);

    models.user.belongsTo(models.association);

    //models.user.belongsTo(models.user_gallery, { onDelete: 'CASCADE' });

  };
  return user;
};