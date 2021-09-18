
echo "Cloning Repository"
# Setup git
CLONE_DIR=$(mktemp -d)
git status
ls -la "$CLONE_DIR"

echo "Composer Install"

composer install

composer dump-autoload -o

ls -la "$CLONE_DIR"