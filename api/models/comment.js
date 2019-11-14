'use strict';
module.exports = (sequelize, DataTypes) => {
    const comment = sequelize.define('comment', {
        product_id: DataTypes.INTEGER,
        user_id: {
            type: DataTypes.INTEGER,
            references: 'user',
            referencesKey: 'id'
        },
        gallery_id: {
            type: DataTypes.INTEGER,
            references: 'gallery',
            referencesKey: 'id'
        },
        content: DataTypes.STRING,
        created_at: DataTypes.DATE
    }, {
        timestamps: false,
        underscored: true,
        freezeTableName: true,
        tableName: 'comment'
    });
    comment.associate = function (models) {
        // associations can be defined here
        models.comment.belongsTo(models.user);

        models.comment.belongsTo(models.gallery);
    };
    return comment;
};