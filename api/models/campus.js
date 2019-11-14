'use strict';
module.exports = (sequelize, DataTypes) => {
  const campus = sequelize.define('campus', {
    name: DataTypes.STRING
  }, {
      timestamps: false,
      underscored: true,
      freezeTableName: true,
      tableName: 'campus'
  });
  campus.associate = function(models) {
    // associations can be defined here
    models.campus.hasMany(models.user, { onDelete: 'CASCADE' });
  };
  return campus;
};