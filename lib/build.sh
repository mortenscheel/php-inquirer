#!/bin/bash

set -e  # Exit immediately if a command exits with a non-zero status.
cd $(dirname "$0")
mkdir -p bin
rm -f bin/*

echo "Building for macOS ARM..."
cargo build --release --target aarch64-apple-darwin
cp target/aarch64-apple-darwin/release/prompter bin/mac-arm

echo "Building for macOS Intel..."
cargo build --release --target x86_64-apple-darwin
cp target/x86_64-apple-darwin/release/prompter bin/mac-x86_64

echo "Building for Windows x64..."
cargo build --release --target x86_64-pc-windows-gnu
cp target/x86_64-pc-windows-gnu/release/prompter.exe bin/win-x86_64.exe

echo "Building for Linux x64..."
cargo build --release --target x86_64-unknown-linux-gnu
cp target/x86_64-unknown-linux-gnu/release/prompter bin/linux-x86_64

echo "Build complete."
