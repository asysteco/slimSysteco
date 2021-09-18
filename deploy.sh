
echo "Cloning Repository"
# Setup git

git checkout deploy
git pull origin develop

ls -la ./

echo "Composer Install"

composer install

composer dump-autoload -o

ls -la ./

git add .
git commit -m "Update deploy"
git push