
echo "Cloning Repository"
# Setup git
CLONE_DIR=$(mktemp -d)
git status
ls -la ./

echo "Composer Install"

composer install

ls -la ./