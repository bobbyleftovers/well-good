# SmartFormat WordPress Plugin

## How to publish

### sync with WordPress Subversion

```
git svn init -s http://plugins.svn.wordpress.org/smartformat
git checkout master
git svn rebase
```

### publish

```
git svn dcommit
```
