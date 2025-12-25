-- Grant all permissions to the admin user (lookup by username)
INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_admins', user_id FROM users WHERE username = 'admin'
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_users', user_id FROM users WHERE username = 'admin'
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_sellers', user_id FROM users WHERE username = 'admin'
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_products', user_id FROM users WHERE username = 'admin'
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_categories', user_id FROM users WHERE username = 'admin'
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'view_reports', user_id FROM users WHERE username = 'admin'
ON DUPLICATE KEY UPDATE permission_id=permission_id;

INSERT INTO admin_permissions (user_id, permission_name, granted_by)
SELECT user_id, 'manage_settings', user_id FROM users WHERE username = 'admin'
ON DUPLICATE KEY UPDATE permission_id=permission_id;
