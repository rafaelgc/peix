cp -R frontend/src/* .
mkdir api
cp -R backend/* api
rm -R frontend
rm -R backend
cd api
php composer.phar install
rm deploy.sh